module.exports = {
	globDirectory: 'public/',
	globPatterns: [
		'**/*.{css,png,svg,js,json,woff,woff2,eot,ttf,less,jpg,ico,scss,php,mp3,txt}'
	],
	swDest: 'public/sw.js',
	ignoreURLParametersMatching: [
		/^utm_/,
		/^fbclid$/,
		/^rent-n-play/,
		/^camping-rent/
	]
};