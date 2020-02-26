import React from 'react';
import Choices from "../sale-offer/Choices";
import SaleOfferTimerModify from "../sale-offer/SaleOfferTimerModify";
import PageHeader from "../../common/PageHeader";

export default class HomePageAppearance extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			action: 'edit',
			title: 'Edit'
		};
	}

	componentDidMount() {
		setInterval(() => this.handleTimer(), 2000);
	}

	handleTimer() {
		if (this.state.action === 'edit') {
			this.setState({
				action: 'update',
				title: 'Update'
			});
		} else {
			this.setState({
				action: 'edit',
				title: 'Edit'
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
			return <Choices/>
		} else if (this.state.action === 'update') {
			return <SaleOfferTimerModify/>
		} else {
			return <Choices/>
		}
	}

	render() {
		return (
			<div className="row">
				<div className="col-12">
					<div className="card shadow-sm custom-card">
						<div className="card-header py-0">
							<PageHeader title={this.state.title} action={this.handleAction} text={''}/>
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