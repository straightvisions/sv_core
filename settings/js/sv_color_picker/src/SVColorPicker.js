import ColorPicker from './components/ColorPicker';

export default {
    // Returns the color value as RGB format
    getRGB: function (color) {
        var c;
    
        // Color is hex
        if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(color)){
            c= color.substring(1).split('');
            if(c.length== 3){
                c= [c[0], c[0], c[1], c[1], c[2], c[2]];
            }
            c= '0x'+c.join('');
            return ''+[(c>>16)&255, (c>>8)&255, c&255].join(',')+',1';
        }
    
        return color;
    },
    
    renderColorPicker: function ( id, color, colorPalette ) {
        const inputElement = jQuery( '[id="' + id + '"]' );

        if ( inputElement.length > 0 ) {
            const rootElement = inputElement.parent();
    
           if ( rootElement.length > 0 ) {
                // Output
                ReactDOM.render(
                    <ColorPicker 
                        id={ id }
                        color={ this.getRGB( color ? color : '0,0,0,1' ) } 
                        colorPalette={ colorPalette }
                        rootElement={ rootElement[0] }
                    />, 
                    rootElement[0]
                );
           }
        }
    },
    
    // Loops through the registered color settings and replaces them with the GB color picker
    loadColorPicker: function ( color_picker ) {
        if ( Object.keys( color_picker ).length > 0 ) {
            for (const [key, color] of Object.entries( color_picker ) ) {
   
                if ( key !== 'color_palette' ) {
                    const colorPalette = color_picker.color_palette ? color_picker.color_palette : false;

                    this.renderColorPicker( key, color, colorPalette );
                }
            }
        }
    }
};