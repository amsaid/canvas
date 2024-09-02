import Quill from 'quill';

let BlockEmbed = Quill.import('blots/block/embed');
let Container = Quill.import('blots/container');
let Block = Quill.import('blots/block');
let Inline = Quill.import('blots/inline');
let TextBlock = Quill.import('blots/text');

class DivBlot extends Container {
  //
}

DivBlot.blotName = 'div';
DivBlot.tagName = 'div';
DivBlot.allowedChildren = [TextBlock, Block, Inline, BlockEmbed]

export default DivBlot;
