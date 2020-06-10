import React from 'react';
import Choices from "../sale-offer/Choices";
import SaleOfferTimerModify from "../sale-offer/SaleOfferTimerModify";
import PageHeader from "../../common/PageHeader";
import ShopEnum from "../ShopEnum";
import Sliders from "../sale-offer/Sliders";

export default class HomePageAppearance extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			action: ShopEnum.Choices,
			title: [
				'Customize Shop Homepage Appearance',
				'Customize Header Sliders',
				'Modify Sale Offer Timer Details'
			]
		};
	}

	handleAction = (value) => {
		this.setState({
			action: value
		});
	};

	renderElement = () => {
		switch (this.state.action) {
			case ShopEnum.Sliders:
				return <Sliders/>;

			case ShopEnum.ModifySaleOfferTimer:
				return <SaleOfferTimerModify/>;

			default:
				return <Choices handleAction={this.handleAction}/>
		}
	};

	render = () => {
		return (
			<div className="row">
				<div className="col-12">
					<div className="card shadow-sm custom-card">
						<div className="card-header py-0">
							<PageHeader title={this.state.title[this.state.action]} action={this.handleAction} text={''}/>
						</div>
						{
							this.renderElement()
						}
					</div>
				</div>
			</div>
		);
	}
}