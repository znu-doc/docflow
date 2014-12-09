  //--------------------
  /*
	Browser Detect set v1.0
	Detects the browser and its version (IE6+, Opera, Firefox, Chrome, Safari )
	
	http://xiper.net/
	
	Author Andrei Kosyack
*/


function browserDetectNav(chrAfterPoint)
{
var 	UA=window.navigator.userAgent,
	OperaB = /Opera[ \/]+\w+\.\w+/i,
	OperaV = /Version[ \/]+\w+\.\w+/i,	
	FirefoxB = /Firefox\/\w+\.\w+/i,
	ChromeB = /Chrome\/\w+\.\w+/i,
	SafariB = /Version\/\w+\.\w+/i,
	IEB = /MSIE *\d+\.\w+/i,
	SafariV = /Safari\/\w+\.\w+/i,
	browser = new Array(),
	browserSplit = /[ \/\.]/i,
	OperaV = UA.match(OperaV),
	Firefox = UA.match(FirefoxB),
	Chrome = UA.match(ChromeB),
	Safari = UA.match(SafariB),
	SafariV = UA.match(SafariV),
	IE = UA.match(IEB),
	Opera = UA.match(OperaB);
		
		if ((!Opera=="")&(!OperaV=="")) browser[0]=OperaV[0].replace(/Version/, "Opera")
				else 
					if (!Opera=="")	browser[0]=Opera[0]
						else
							if (!IE=="") browser[0] = IE[0]
								else 
									if (!Firefox=="") browser[0]=Firefox[0]
										else
											if (!Chrome=="") browser[0] = Chrome[0]
												else
													if ((!Safari=="")&&(!SafariV=="")) browser[0] = Safari[0].replace("Version", "Safari");

	var outputData;
	
	if (browser[0] != null) outputData = browser[0].split(browserSplit);
	if (((chrAfterPoint == null)|(chrAfterPoint == 0))&(outputData != null)) 
		{
			chrAfterPoint=outputData[2].length;
			outputData[2] = outputData[2].substring(0, chrAfterPoint);
			return(outputData);
		}
			else
				if (chrAfterPoint != null) 
				{
					outputData[2] = outputData[2].substr(0, chrAfterPoint);
					return(outputData);					
				}
					else	return(false);
}

function browserDetectJS() {
var
	browser = new Array();
	
	if (window.opera) {
		browser[0] = "Opera";
		browser[1] = window.opera.version();
	}
		else 
		if (window.chrome) {
			browser[0] = "Chrome";
		}
			else
			if (window.sidebar) {
				browser[0] = "Firefox";
			}
				else
					if ((!window.external)&&(browser[0]!=="Opera")) {
						browser[0] = "Safari";
					}
						else
						if (window.ActiveXObject) {
							browser[0] = "MSIE";
							if (window.navigator.userProfile) browser[1] = "6"
								else 
									if (window.Storage) browser[1] = "8"
										else 
											if ((!window.Storage)&&(!window.navigator.userProfile)) browser[1] = "7"
												else browser[1] = "Unknown";
						}
	
	if (!browser) return(false)
		else return(browser);
}

function getBrowser(chrAfterPoint) {
	var
		browserNav = browserDetectNav(chrAfterPoint),
		browserJS = browserDetectJS();

	if (browserNav[0] == browserJS[0]) return(browserNav)
		else
			if (browserNav[0] != browserJS[0]) return(browserJS)
				else
					return(false);
}


function isItBrowser(browserCom, browserVer, detectMethod) {
var browser;

switch (detectMethod) {
	case 1: browser = browserDetectNav(); break;
	case 2: browser = browserDetectJS(); break;
	default: browser = getBrowser();
};

	if ((browserCom == browser[0])&(browserVer == browser[1])) return(true)
		else
			if ((browserCom == browser[0])&((browserVer == null)||(browserVer == 0))) return(true)
				else return(false);
};