import React from 'react';

export default class PageHeader extends React.Component {
	constructor(props) {
		super(props);
	}

	render() {
		return (
			<div className="row">
				<div className="col-8">
					<h5 className="page-title animatable">{this.props.title}</h5>
				</div>
				<div className="col-4 my-auto">
					<a className="float-right btn btn-outline-primary waves-effect waves-light shadow-sm fadeInRightBig" href="javascript:void(0);" onClick="{this.props.handleClick}">{this.props.text}</a>
				</div>
			</div>
		);
	}
}