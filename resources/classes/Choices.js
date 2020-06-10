export default class Choices {
	constructor() {
		this.chosen = [{
			key: 1,
			values: [1]
		}];
		this.optionA = {
			key: 1,
			value: 1
		};
		this.optionB = {
			key: 1,
			value: 2
		};
		this.optionC = {
			key: 2,
			value: 2
		};
		this.optionD = {
			key: 1,
			value: 3
		};
		this.optionE = {
			key: 2,
			value: 3
		};
	}

	perform = () => {
		this.handleAddItem(this.optionA);
		this.handleAddItem(this.optionB);
		this.handleAddItem(this.optionC);
		this.handleAddItem(this.optionD);
		this.handleAddItem(this.optionE);
		console.log(this.chosen);
	};

	handleAddItem = (option) => {
		let foundKey = false;
		let foundValue = false;
		this.chosen.map((item) => {
			if (item.key === option.key) {
				foundKey = true;
				item.values.forEach((v) => {
					if (v === option.value) {
						foundValue = true;
					}
				});
				if (!foundValue)
					item.values.push(option.value);
			}
		});
		if (!foundKey)
			this.chosen.push({
				key: option.key,
				values: [option.value]
			});
	};
}

Choices.prototype.setup = () => {
	const choices = new Choices();
	choices.perform();
};