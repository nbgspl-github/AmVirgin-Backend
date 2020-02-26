import React, {Component} from 'react';
import ReactDOM from 'react-dom';

export default class SaleOfferTimerEdit extends Component {
	render() {
		return (
			<div className="row">
				<div className="col-12">
					<div className="card shadow-sm custom-card">
						<div className="card-header py-0">
							@include('admin.extras.header', ['title'=>'Customize Shop Appearance'])
						</div>
						<div className="card-body animatable">
							<div className="row">
								<div className="col-sm-6 pr-0">
									<div className="card shadow-sm border animated slideInDown">
										<div className="card-body">
											<h5 className="card-title">Sliders</h5>
											<p className="card-text">Choose this to update sliders which show up in the header section of shop homepage.</p>
											<a href="" className="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i className="mdi mdi-arrow-right"></i></a>
										</div>
									</div>
								</div>
								<div className="col-sm-6 mr-0">
									<div className="card shadow-sm border animated slideInDown">
										<div className="card-body">
											<h5 className="card-title">Sale Offer Timer</h5>
											<p className="card-text">Choose this to offer timer details such as displayed text, remaining time, etc.</p>
											<a href="{{route('admin.shop.sale-offer-timer.edit')}}" className="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i className="mdi mdi-arrow-right"></i></a>
										</div>
									</div>
								</div>
							</div>
							<div className="row mt-3">
								<div className="col-sm-6 pr-0">
									<div className="card shadow-sm border animated slideInDown">
										<div className="card-body">
											<h5 className="card-title">Brands in Focus</h5>
											<p className="card-text">Choose this to modify categories which will show up with a banner on the homepage.</p>
											<a href="" className="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i className="mdi mdi-arrow-right"></i></a>
										</div>
									</div>
								</div>
								<div className="col-sm-6 mr-0">
									<div className="card shadow-sm border animated slideInDown">
										<div className="card-body">
											<h5 className="card-title">Today's Deals</h5>
											<p className="card-text">Choose this to marks products which will show up in today's hot deals.</p>
											<a href="" className="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i className="mdi mdi-arrow-right"></i></a>
										</div>
									</div>
								</div>
							</div>
							<div className="row mt-3">
								<div className="col-sm-6 pr-0">
									<div className="card shadow-sm border animated slideInDown">
										<div className="card-body">
											<h5 className="card-title">Popular Stuff</h5>
											<p className="card-text">Choose this to update which categories will shop up in popular stuff section.</p>
											<a href="" className="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i className="mdi mdi-arrow-right"></i></a>
										</div>
									</div>
								</div>
								<div className="col-sm-6 mr-0">
									<div className="card shadow-sm border animated slideInDown">
										<div className="card-body">
											<h5 className="card-title">Trending Now</h5>
											<p className="card-text">Choose this to update what categories will show up in trending now section.</p>
											<a href="" className="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i className="mdi mdi-arrow-right"></i></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		);
	}
}

if (document.getElementById('react')) {
	ReactDOM.render(<SaleOfferTimerEdit/>, document.getElementById('example'));
}
