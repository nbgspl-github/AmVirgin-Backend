import React from 'react';

export default class ProgressModal extends React.Component {
	constructor() {
		super();
	}

	render() {
		return (
			<div className="modal fade" id="progressModal" data-backdrop="static" tabIndex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
				<div className="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px">
					<div className="modal-content shadow" style="border-radius: 16px">
						<div className="modal-header">
							<h5 className="modal-title mx-auto" id="staticBackdropLabel">{this.props.title}</h5>
						</div>
						<div className="modal-body">
							<div className="row">
								<div className="col-auto mx-auto text-center">
									<div id="progressCircle" data-percent="0" className="medium m-0" data-progressBarColor="#cf3f43">
									</div>
									<span id="progressPercent" className="text-primary font-20">{this.props.percentDone}%</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		);
	}
}