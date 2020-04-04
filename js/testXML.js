var rootURL = "http://localhost/PrestaAPI-Testing/index.php";
$(document).ready(function()
{
   var sMyString = '<q id="a"><b id="b">hey!</b></a>';
var oParser = new DOMParser();
var oDOM = oParser.parseFromString(sMyString, "application/xml");
// print the name of the root element or error message
console.log(oDOM.documentElement.nodeName == "parsererror" ? "error while parsing" : oDOM.documentElement.nodeName);

document.getElementById('testprintLabel').addEventListener("click", formToXml);
});

var testAlert = function()
{
    alert("Hello there");
};


var formToXml = function()
{
    var name = "<firstName>" + $("#firstname").val() + "</firstname>";
    alert(name);
};


