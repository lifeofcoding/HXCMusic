/* Custom version of http://backbonetutorials.com/seo-for-single-page-apps/ */
var page = require('webpage').create(),
	system = require('system'),
	lastReceived = new Date().getTime(),
	requestCount = 0,
	responseCount = 0,
	requestIds = [],
	startTime = new Date().getTime(),
	checkCompleteInterval = null,
	timeout = 20000; //how long to wait

page.onResourceReceived = function (response) {
    if(requestIds.indexOf(response.id) !== -1) {
        lastReceived = new Date().getTime();
        responseCount++;
        requestIds[requestIds.indexOf(response.id)] = null;
    }
};
page.onResourceRequested = function (request) {
    if(requestIds.indexOf(request.id) === -1) {
        requestIds.push(request.id);
        requestCount++;
    }
};

// Open the page
page.open(system.args[1], function () {});

var checkComplete = function () {
	//console.log('checking complete');
  // We don't allow it to take longer than 5 seconds but //changed to 30sec
  // don't return until all requests are finished
  if((new Date().getTime() - lastReceived > 300 && requestCount === responseCount) || new Date().getTime() - startTime > timeout)  {
    clearInterval(checkCompleteInterval);
	//console.log('timeout');
    console.log(page.content);
    phantom.exit();
  }
};
// Let us check to see if the page is finished rendering
checkCompleteInterval = setInterval(checkComplete, 1);