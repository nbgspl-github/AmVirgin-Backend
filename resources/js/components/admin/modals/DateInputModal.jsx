import React from 'react';
import Modal from "react-bootstrap/Modal";
import Button from "react-bootstrap/Button";

export default class DateInputModal extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			show: this.props.show
		};
	}

	render = () => {
		return (
			<Modal show={this.props.show} onHide={this.props.handleHide}>
				<Modal.Header closeButton>
					<Modal.Title>Modal heading</Modal.Title>
				</Modal.Header>
				<Modal.Body>Woohoo, you're reading this text in a modal!</Modal.Body>
				<Modal.Footer>
					<Button variant="secondary" onClick={this.props.handleHide}>
						Close
					</Button>
					<Button variant="primary" onClick={this.props.handleHide}>
						Save Changes
					</Button>
				</Modal.Footer>
			</Modal>
		);
	}
}