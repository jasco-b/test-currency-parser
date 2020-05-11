import React from "react";
import axios from 'axios';
import Pagination from "./Pagination";
import DatePicker from "react-datepicker";

import "react-datepicker/dist/react-datepicker.css";


class CurrencyList extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            data: [],
            isLoaded: false,
            currentPage: 1,
            searchData: {},
            search: React.createRef(),
            from: '',
            to: ''
        };

        this.getData.bind(this);
        this.press.bind(this);
        this.submit.bind(this);

    }


    componentDidMount() {
        this.getData();
    }

    render() {
        const data = this.state.data;
        console.log('data', data);

        if (!this.state.isLoaded) {
            return (
                <div>
                    Data is rendering
                </div>
            )
        }


        let body = data.data.map((item) => {
            return (
                <tr key={item.id}>
                    <td>{item.valuteID}</td>
                    <td>{item.charCode}</td>
                    <td>{item.numCode}</td>
                    <td>{item.value}</td>
                    <td>{item.nominal}</td>
                    <td>{item.date}</td>
                </tr>
            )
        });


        if (data.data.length === 0) {
            body = (<tr>
                <td colSpan={6} className={'text-center'}>No data Found</td>
            </tr>)
        }


        return (
            <div className='Currency list'>
                <h2>Currency list</h2>

                <form action="" onSubmit={this.submit.bind(this)} className={'mb-1'}>
                    <div className="row">

                        <div className="col-lg-4 col-md-6">
                            <div className="form-group">
                                <label htmlFor="">Valute</label>
                                <input type="text" className={'form-control'}
                                       placeholder={'Please write valute ID for filter'} ref={this.state.search}/>
                            </div>

                        </div>
                        <div className="col-lg-8">
                            <div className="row">
                                <div className="col-lg-6">
                                    <div className="form-group">
                                        <label htmlFor="">From</label>
                                        <DatePicker
                                            selected={this.state.from}
                                            onChange={(date) => this.setState({
                                                from: date,
                                            })}
                                            dateFormat="dd.MM.yyyy"
                                            className={'form-control'}
                                            name={'from'}
                                        />
                                    </div>
                                </div>
                                <div className="col-lg-6">
                                    <div className="form-group">
                                        <label htmlFor="">To</label>
                                        <DatePicker
                                            selected={this.state.to}
                                            onChange={(date) => this.setState({
                                                to: date,
                                            })}
                                            dateFormat="dd.MM.yyyy"
                                            className={'form-control'}
                                            name={'to'}
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type={'submit'} className={'btn btn-success'}>Submit</button>
                </form>
                <table className='table '>
                    <thead>
                    <tr>
                        <th>
                            Valute
                        </th>
                        <th>
                            Char code
                        </th>
                        <th>
                            Num code
                        </th>
                        <th>
                            Value
                        </th>

                        <th>
                            Nominal
                        </th>

                        <th>
                            Date
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    {body}
                    </tbody>
                </table>

                <Pagination meta={data.meta} press={(page) => this.press(page)}/>
            </div>
        );
    }

    getData(page = 1, data) {
        const mainThis = this;

        const searchData = data ? data : this.state.searchData;
        searchData.page = page;

        axios.get('/api/currencies/prices', {
            params: searchData,
        }).then((res) => {
            mainThis.setState({
                data: res.data,
                isLoaded: true,
                currentPage: page
            })
        });
    }

    press(page) {
        this.getData(page);
    }

    submit(e) {
        e.preventDefault();
        const searchData =  {
            valute: this.state.search.current ? this.state.search.current.value : "",
            from: this.state.from ? this.formatDate(this.state.from) : '',
            to: this.state.to ? this.formatDate(this.state.to) : '',
        };

        this.setState({
            searchData
        });

        this.getData(1, searchData);
    }

    formatDate(date) {
        const notFormatedDate = new Date(date);
        let day = notFormatedDate.getDate();
        day = (day + '').length === 1 ? '0' + day : day;

        let month = notFormatedDate.getMonth() + 1;
        month = (month + '').length === 1 ? '0' + month : month;

        return day + '.' + month + '.' + notFormatedDate.getFullYear();
    }

}

export default CurrencyList;
