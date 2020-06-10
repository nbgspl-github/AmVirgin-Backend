import React from 'react'
import ProgressModal from "./modals/ProgressModal";

export default class App extends React.Component {
	constructor(props) {
		super(props);
		this.progressModalRef = React.createRef();
	}


	handleClick = () => {

	};

	render() {
		return (
			<React.Fragment>
				<button className={'btn btn-primary'} onClick={this.handleClick}>Click Me</button>
			</React.Fragment>
		);
	}
}