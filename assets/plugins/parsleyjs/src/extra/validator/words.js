(function () {
// minwords, maxwords, words extra validators
var countWords = function (string) {
  return string
      .replace( /(^\s*)|(\s*$)/gi, "" )
      .replace( /[ ]{2,}/gi, " " )
      .replace( /\n /, "\n" )
      .split(' ').length;
};

window.ParsleyValidator.addValidator(
	'minwords',
	function (value, nbWords) {
		return countWords(value) >= nbWords;
	}, 32)
	.addMessage('en', 'minwords', 'This value needs more words');

window.ParsleyValidator.addValidator(
	'maxwords',
	function (value, nbWords) {
		return countWords(value) <= nbWords;
	}, 32)
	.addMessage('en', 'maxwords', 'This value needs fewer words');

window.ParsleyValidator.addValidator(
	'words',
	function (value, arrayRange) {
		var length = countWords(value);
		return length >= arrayRange[0] && length <= arrayRange[1];
	}, 32)
	.addMessage('en', 'words', 'This value has the incorrect number of words');
})();
