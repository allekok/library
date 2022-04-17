function postUrl(url, request, callback) {
	const client = new XMLHttpRequest()
	client.open('post', url)
	client.onload = () => callback(client.responseText)
	client.setRequestHeader('Content-type',
				'application/x-www-form-urlencoded')
	client.send(request)
}

function getUrl(url, callback) {
	const client = new XMLHttpRequest()
	client.open('get', url)
	client.onload = () => callback(client.responseText)
	client.send()
}

function set_cookie(name, value, days=1000, path='/') {
	const expires = new Date((new Date).getTime() +
				 days * 24 * 3600 * 1000).toUTCString()
	const cookie = `${name}=${value};expires=${expires};path=${path}`
	document.cookie = cookie
	return cookie
}

function toggle(label, target) {
	const icon = label.querySelector('.icon')
	if(target.style.display != 'block') {
		target.style.display = 'block'
		icon.innerText = 'keyboard_arrow_up'
	}
	else {
		target.style.display = 'none'
		icon.innerText = 'keyboard_arrow_down'
	}
}

function set_lang(lang, e) {
	e.preventDefault()
	set_cookie('lang', lang)
	window.location.reload()
}

function set_table(table, e) {
	e.preventDefault()
	set_cookie('table', table)
	window.location.reload()
}

function set_limit(el_id) {
	let val = document.getElementById(el_id).value
	val = num_convert(val, 'ckb', 'en')
	val = num_convert(val, 'fa', 'en')
	if(parseInt(val) !== NaN)
		set_cookie('limit', val)
}

function num_convert(inp='', f, t) {
	if(f == t)
		return inp
	const nums = {
		en: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
		fa: ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
		ckb: ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩']
	}
	for(const i in nums[f])
		inp = inp.replace(new RegExp(nums[f][i], 'g'), nums[t][i])
	return inp
}
