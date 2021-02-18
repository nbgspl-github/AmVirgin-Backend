class ChunkUploader {
	progressValue = 0;
	url = null;
	file = null;
	chunkCount = 0;
	chunkSize = 1024000;
	token = null;
	current = 0;
	progressCallback = null;

	constructor(config) {
		this.url = config.url;
		this.file = config.file;
		this.token = config.token;
		this.progressCallback = config.progress;
	}

	prepare = () => {
		this.chunkSize = this.file.size / 8;
		this.chunkCount = 8;
	}

	async sendHeader() {
		let data = new FormData();
		data.append('is_start', '');
		data.append('chunkCount', this.chunkCount);
		data.append('token', this.token);
		return axios.post(this.url, data);
	}

	start = () => {
		for (let i = 1; i <= 8; i++) {
			let chunk = this.file.slice(current, current + this.chunkSize);
			current += this.chunkSize;
			let data = new FormData();
			data.append('chunkNumber', i);
			data.append('token', this.token);
			data.append('file', chunk, this.file.name);
			if (i === 8) {
				data.append('is_end', '');
			}
			axios.post(this.url, data, {
				onUploadProgress: (value) => {
					let percentCompleted = Math.round((value.loaded * 100) / value.total);
					this.progressValue += percentCompleted;
					let actual = this.progressValue / 8;
					console.log(actual);
					// this.progressCallback(actual);
				},
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			})
		}
	}
}