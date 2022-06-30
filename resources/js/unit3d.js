/*
* Here we take all these scripts and compile them into a single 'unit3d.js' file that will be loaded after 'app.js'
*
* Note: The order of this array will matter, no different then linking these assets manually in the html
*/
require('./unit3d/tmdb');
require('./unit3d/parser');
require('./unit3d/helper');
require('./unit3d/custom');