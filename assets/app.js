/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import $ from 'jquery';
window.$ = window.jQuery = $;

import signature_pad from 'signature_pad';
window.signature_pad = signature_pad;

import QRCode from 'qrcode';
window.QRCode = QRCode;

import jsPDF from 'jspdf';
window.jsPDF = jsPDF;

import jQCloud from  'jqcloud2';
window.jQCloud = jQCloud;

import { Chart, registerables } from 'chart.js';
Chart.register(...registerables); 
window.Chart = Chart;

import 'popper.js';
import 'bootstrap';

import 'bootstrap/dist/css/bootstrap.min.css';

import './styles/jqcloud.min.css';

import './styles/app.css';

import './styles/tools/_self-evaluation.css';
import './styles/tools/_signature.css';
import './styles/tools/_word-cloud.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
