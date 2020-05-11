import React from "react";

import Header from "./Header";
import Footer from "./Footer";
import ReactDOM from "react-dom";
import CurrencyList from "./CurrencyList";

function App() {
    return (
        <div className="App">
        <Header/>
        <main role="main" className="container">
        <CurrencyList />
       </main>
        <Footer/>
    </div>
);
}

export default App;


if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}
