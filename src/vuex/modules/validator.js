const validator = {
	getters: {
		isIP4Valid: () => (ip) => {
			return ip.match(/^(([1-9]?\d|1\d\d|2[0-5][0-5]|2[0-4]\d)\.){3}([1-9]?\d|1\d\d|2[0-5][0-5]|2[0-4]\d)$/)
		},
		isMaskValid: () => (mask) => {
			if (mask.match(/^[0-9]+$/)) {
				let num = parseInt(mask, 10)
				return num >= 0 && num <= 32
			} else {
				let parts = mask.split(".")
				if (parts.length != 4) return false
				let netArea = true
				for (let i = 0; i < 4; i++) {
					let num
					if (parts[i].match(/^[0-9]+$/)) {
						num = parseInt(parts[i], 10)
						if (num < 0 || num > 255) return false
					} else {
						return false
					}
					for (let j = 7; j >= 0; j--)
						if (num & (1 << j)) {
							if (!netArea) return false
						} else { // 0
							netArea = false
						}
				}
				return true
			}
		}
	}
}

export default validator
