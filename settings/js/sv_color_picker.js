// Gutenberg Components
const { 
    createElement,
    render
} = wp.element;
const { ColorPicker }   = wp.components;
const { withState }     = wp.compose;

// Own Component
class SVColorPicker extends React.Component {
    constructor(props) {
        super(props)

        const colorObject   = new Object;
        colorObject.r       = parseInt( props.color.split(',')[0] );
        colorObject.g       = parseInt( props.color.split(',')[1] );
        colorObject.b       = parseInt( props.color.split(',')[2] );
        colorObject.a       = parseFloat( props.color.split(',')[3] );

        this.state          = {
            color: colorObject,
            element: props.element,
        };
    }
    
    render() {
        return(
            createElement( 'div', null, 
                createElement( ColorPicker, {
                    color: this.state.color,
                    onChangeComplete: ( value ) => {
                        return this.setState({
                            color: value.rgb
                        });
                    }
                }), 
                createElement( 'input', {
                    value: Object.values( this.state.color ).join(','),
                    "data-sv_type": "sv_form_field",
                    class: "sv_input",
                    id: this.state.element,
                    name: this.state.element,
                    type: "hidden"
                })
            )
        );
    }
}

// Converts a hex value to an rgba value
// Function from: https://stackoverflow.com/questions/21646738/convert-hex-to-rgba
function hexToRgbA(hex){
    var c;
    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
        c= hex.substring(1).split('');
        if(c.length== 3){
            c= [c[0], c[0], c[1], c[1], c[2], c[2]];
        }
        c= '0x'+c.join('');
        return ''+[(c>>16)&255, (c>>8)&255, c&255].join(',')+',1';
    }
    throw new Error('Bad Hex');
}

function renderColorPicker( element, color ) {
    const rootElement = jQuery( '#' + element ).parent();

    // If color is not set
    if ( ! color ) {
        color = '0,0,0,1';
    }

    if ( color.startsWith('#') ) {
        color = hexToRgbA( color );
    }

    if ( rootElement.length > 0 ) {
        // Removes the old color picker
        rootElement.html('');

        // Output
        render(
            createElement( SVColorPicker, { color: color, element: element } ),
            rootElement[0]
        );
    }
}

// Edits the color picker wie jQuery
function editColorPicker() {
    const alphaIputs = jQuery( '.components-color-picker .components-base-control__field input[type="number"]' );
    
    alphaIputs.map( e => {
        if ( jQuery(alphaIputs[ e ]).attr( 'step' ) ) {
            jQuery(alphaIputs[ e ]).attr( 'step', 0.01 )
        }
    });
}

// Loops through the registered color settings and replaces them with the GB color picker
function loadColorPicker() {
    Object.keys( sv_core_color_picker ).map( element => {
        renderColorPicker( element, sv_core_color_picker[ element ] );
    } );

    editColorPicker();
}

loadColorPicker();