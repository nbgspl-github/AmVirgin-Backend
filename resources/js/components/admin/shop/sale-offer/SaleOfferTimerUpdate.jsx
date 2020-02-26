import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import PageHeader from "../../common/PageHeader";

export default class SaleOfferTimerUpdate extends Component {
	handleAction(value) {
		event.preventDefault();
		console.log('Rendering update');
	};

	render() {
		return (
			<div className="card-body animatable">
				<div className="row">
					<div className="col-sm-6 pr-0">
						<div className="card shadow-sm border animated">
							<div className="card-body">
								<h5 className="card-title">Sliders</h5>
								<p className="card-text">Choose this to update sliders which show up in the header section of shop homepage.</p>
								<a href="" className="btn btn-primary shadow-primary" data-name="sliders" onClick={this.handleAction.bind(this, 'sliders')}>Edit&nbsp;&nbsp;<i className="mdi mdi-arrow-right"></i></a>
							</div>
						</div>
					</div>
					<div className="col-sm-6 mr-0">
						<div className="card shadow-sm border animated">
							<div className="card-body">
								<h5 className="card-title">Sale Offer Timer</h5>
								<p className="card-text">Choose this to offer timer details such as displayed text, remaining time, etc.</p>
								<a href="#!" className="btn btn-primary shadow-primary" data-name={'saleOfferTime'} onClick={this.handleAction.bind(this, 'saleOfferTime')}>Edit&nbsp;&nbsp;<i className="mdi mdi-arrow-right"></i></a>
							</div>
						</div>
					</div>
				</div>
				<div className="row mt-3">
					<div className="col-sm-6 pr-0">
						<div className="card shadow-sm border animated">
							<div className="card-body">
								<h5 className="card-title">Brands in Focus</h5>
								<p className="card-text">Choose this to modify categories which will show up with a banner on the homepage.</p>
								<a href="" className="btn btn-primary shadow-primary" data-name={'brandsInFocus'} onClick={this.handleAction.bind(this)}>Edit&nbsp;&nbsp;<i className="mdi mdi-arrow-right"></i></a>
							</div>
						</div>
					</div>
					<div className="col-sm-6 mr-0">
						<div className="card shadow-sm border animated">
							<div className="card-body">
								<h5 className="card-title">Today's Deals</h5>
								<p className="card-text">Choose this to marks products which will show up in today's hot deals.</p>
								<a href="" className="btn btn-primary shadow-primary" data-name={'todaysDeals'} onClick={this.handleAction.bind(this)}>Edit&nbsp;&nbsp;<i className="mdi mdi-arrow-right"></i></a>
							</div>
						</div>
					</div>
				</div>
				<div className="row mt-3">
					<div className="col-sm-6 pr-0">
						<div className="card shadow-sm border animated">
							<div className="card-body">
								<h5 className="card-title">Popular Stuff</h5>
								<p className="card-text">Choose this to update which categories will shop up in popular stuff section.</p>
								<a href="" className="btn btn-primary shadow-primary" data-name={'popularStuff'} onClick={this.handleAction.bind(this)}>Edit&nbsp;&nbsp;<i className="mdi mdi-arrow-right"></i></a>
							</div>
						</div>
					</div>
					<div className="col-sm-6 mr-0">
						<div className="card shadow-sm border animated">
							<div className="card-body">
								<h5 className="card-title">Trending Now</h5>
								<p className="card-text">Choose this to update what categories will show up in trending now section.</p>
								<a href="" className="btn btn-primary shadow-primary" data-name={'trendingNow'} onClick={this.handleAction.bind(this)}>Edit&nbsp;&nbsp;<i className="mdi mdi-arrow-right"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		);
	}
}