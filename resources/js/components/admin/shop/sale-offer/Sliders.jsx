import React, {Component} from 'react';

export default class Sliders extends Component {
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

	handleAction(value) {
		event.preventDefault();
		this.setState({
			show: !this.state.show
		});
	};

	render() {
		return (
			<div className="card-body animatable">
				Sliders Rendered
			</div>
		);
	}
}