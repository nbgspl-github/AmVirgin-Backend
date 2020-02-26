import React from 'react';
import SaleOfferTimerEdit from "../sale-offer/SaleOfferTimerEdit";
import SaleOfferTimerUpdate from "../sale-offer/SaleOfferTimerUpdate";

export default class HomePageAppearance extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			action: 'edit'
		};
	}

	componentDidMount() {
		setInterval(() => this.handleTimer(), 2000);
	}

	handleTimer() {
		if (this.state.action === 'edit') {
			this.setState({
				action: 'update'
			});
		} else {
			this.setState({
				action: 'edit'
			});
		}
	}

	handleAction(value) {
		this.setState({
			action: value
		});
	}

	renderElement() {
		if (this.state.action === 'edit') {
			return <SaleOfferTimerEdit/>
		} else if (this.state.action === 'update') {
			return <SaleOfferTimerUpdate/>
		} else {
			return <SaleOfferTimerEdit/>
		}
	}

	render() {
		return (
			<React.Fragment>
				{
					this.renderElement()
				}
			</React.Fragment>
		);
	}
}