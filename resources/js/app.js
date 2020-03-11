import ReactDOM from "react-dom";
import React from "react";
import HomePageAppearance from "./components/admin/shop/home-page/HomePageAppearance";
// import 'bootstrap/dist/css/bootstrap.min.css';

require('./components/admin/shop/home-page/HomePageAppearance');
if (document.getElementById('react')) {
	ReactDOM.render(<HomePageAppearance/>, document.getElementById('react'));
}