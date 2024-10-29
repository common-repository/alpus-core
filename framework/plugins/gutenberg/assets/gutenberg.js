/**
 * Alpus Gutenberg Blocks
 *
 * @since 1.2.0
 */

import AlpusTypographyControl, { alpusGenerateTypographyCSS } from './controls/typography';
import AlpusStyleOptionsControl, { alpusGenerateStyleOptionsCSS } from './controls/style-options';
import { alpusGenerateStyleOptionsClass } from './controls/style-options';

window.alpusTypographyControl = AlpusTypographyControl;
window.alpusStyleOptionsControl = AlpusStyleOptionsControl;

import './widgets/heading';
import './widgets/button';
import './widgets/image';
import './widgets/icon-box';
import './widgets/container';
import './widgets/icon';