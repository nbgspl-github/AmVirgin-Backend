import React, {Component} from 'react';
import DateInputModal from "../../modals/DateInputModal";
import ShopEnum from "../ShopEnum";

export default class Choices extends Component {
	constructor(props) {
		super(props);
		this.state = {
			show: false
		};
	}

	handleHideModal = () => {
		this.setState({
			show: !this.state.show
		});
	};

	handleAction = (value) => {
		event.preventDefault();
		this.props.handleAction(value);
	};

	static Item = (props) => {
		const action = props.action.length > 0 ? props.action : 'Edit';
		return (
			<div className="col-sm-6 pr-0">
				<div className="card shadow-sm border animated">
					<div className="card-body">
						<h5 className="card-title">{props.title}</h5>
						<p className="card-text">{props.description}</p>
						<a href="" className="btn btn-primary shadow-primary" data-name="sliders" onClick={props.handleClick}>{action}
							&nbsp;&nbsp;<i className="mdi mdi-arrow-right"></i></a>
					</div>
				</div>
			</div>
		);
	};

	render() {
		return (
			<div className="card-body animatable">
				<div className="row">
					<Choices.Item action={'Edit'} title={'Sliders'} description={'Choose this to update which sliders show up in the header section of homepage.'} handleClick={() => this.handleAction(ShopEnum.Sliders)}/>
					<Choices.Item action={'Edit'} title={'Sale Offer Timer'} description={'Choose this to offer timer details such as displayed text, remaining time, etc.'} handleClick={() => this.handleAction(ShopEnum.Sliders)}/>
				</div>
				<div className="row mt-3">
					<Choices.Item action={'Edit'} title={'Brands in Focus'} description={'Choose this to modify categories which will show up with a banner on the homepage.'} handleClick={() => this.handleAction(ShopEnum.Sliders)}/>
					<Choices.Item action={'Edit'} title={'Today\'s Deals'} description={'Choose this to marks products which will show up in today\'s hot deals.'} handleClick={() => this.handleAction(ShopEnum.Sliders)}/>
				</div>
				<div className="row mt-3">
					<Choices.Item action={'Edit'} title={'Popular Stuff'} description={'Choose this to update which categories will shop up in popular stuff section.'} handleClick={() => this.handleAction(ShopEnum.Sliders)}/>
					<Choices.Item action={'Edit'} title={'Trending Now'} description={'Choose this to update what categories will show up in trending now section.'} handleClick={() => this.handleAction(ShopEnum.Sliders)}/>
				</div>
			</div>
		);
	}
}