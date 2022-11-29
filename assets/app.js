/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import 'bootstrap';

//import { Tooltip, Toast, Popover } from 'bootstrap'

//const getNiceMessage = require('./js/components/get_nice_message')

import getNiceMessage from './js/components/get_nice_message';

//import $ from 'jquery' (package name)
//jeżeli nie ma ./ albo ../ to jest moduł, inaczej szuka relatywnioe to tego pliku
//global.$ = $;
//only for legacy code to jquery works




// start the Stimulus application
import './bootstrap';




