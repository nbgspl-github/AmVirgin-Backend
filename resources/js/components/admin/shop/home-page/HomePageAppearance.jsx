import React from 'react';
import SaleOfferTimerEdit from "../sale-offer/SaleOfferTimerEdit";
import SaleOfferTimerUpdate from "../sale-offer/SaleOfferTimerUpdate";
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
			return <SaleOfferTimerEdit/>
		} else if (this.state.action === 'update') {
			return <SaleOfferTimerUpdate/>
		} else {
			return <SaleOfferTimerEdit/>
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