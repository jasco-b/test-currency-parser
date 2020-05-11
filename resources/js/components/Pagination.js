import React from "react";

const range = (from, to, step = 1) => {
    let i = from;
    const range = [];

    while (i <= to) {
        range.push(i);
        i += step;
    }

    return range;
};

export default class Pagination extends React.Component {


    render() {
        const {current_page, last_page, path, per_page, total} = this.props.meta;
        const press = this.props.press;


        if (last_page === 1) {
            return '';
        }

        const pages = this.getPaginationArray();

        return (
            <nav aria-label="Page navigation ">
                <ul className="pagination">
                    {current_page != 1 && <li className="page-item">
                        <a className="page-link" href="#" onClick={() => press(current_page - 1)}>Previous</a>
                    </li>}
                    {pages.map((page) =>
                        <li className={'page-item ' + (page == current_page ? ' active' : '')} key={page}>
                            <a className="page-link" href="#" onClick={() => press(page)}>
                                {page}
                            </a>
                        </li>)}
                    {current_page != last_page &&
                    <li className="page-item"><a className="page-link" href="#"
                                                 onClick={() => press(current_page + 1)}>Next</a></li>}
                </ul>
            </nav>
        )
    }


    getPaginationArray() {
        const {current_page, last_page, path, per_page, total} = this.props.meta;
        const totalPages = last_page;
        const currentPage = current_page;
        const pageNeighbours = 3;

        /**
         * totalNumbers: the total page numbers to show on the control
         * totalBlocks: totalNumbers + 2 to cover for the left(<) and right(>) controls
         */
        const totalNumbers = (pageNeighbours * 2) + 3;
        const totalBlocks = totalNumbers + 2;

        if (totalPages > totalBlocks) {

            const startPage = Math.max(2, currentPage - pageNeighbours);
            const endPage = Math.min(totalPages - 1, currentPage + pageNeighbours);

            let pages = range(startPage, endPage);

            /**
             * hasLeftSpill: has hidden pages to the left
             * hasRightSpill: has hidden pages to the right
             * spillOffset: number of hidden pages either to the left or to the right
             */
            const hasLeftSpill = startPage > 2;
            const hasRightSpill = (totalPages - endPage) > 1;
            const spillOffset = totalNumbers - (pages.length + 1);

            switch (true) {
                // handle: (1) < {5 6} [7] {8 9} (10)
                case (hasLeftSpill && !hasRightSpill): {
                    const extraPages = range(startPage - spillOffset, startPage - 1);
                    pages = [...extraPages, ...pages];
                    break;
                }

                // handle: (1) {2 3} [4] {5 6} > (10)
                case (!hasLeftSpill && hasRightSpill): {
                    const extraPages = range(endPage + 1, endPage + spillOffset);
                    pages = [...pages, ...extraPages];
                    break;
                }

                // handle: (1) < {4 5} [6] {7 8} > (10)
                case (hasLeftSpill && hasRightSpill):
                default: {
                    pages = [...pages];
                    break;
                }
            }

            return [1, ...pages, totalPages];

        }

        return range(1, totalPages);

    }


}
