import {
    Button,
    Placeholder,
    SelectControl,
} from '@wordpress/components';

const {
    InspectorControls
} = wp.editor;


const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;

registerBlockType('mailerlite/form-block', {
    title: 'Mailerlite sign-up form',
    icon: (<svg width="21px" height="21px" viewBox="0 0 21 21">
        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <g id="mailerlitelogo" transform="translate(0.198319, 0.325455)" fill="#09C269" fill-rule="nonzero">
                <path
                    d="M17.2807581,0.115646258 L2.78853487,0.115646258 C1.28807741,0.115646258 0.0437956203,1.34864717 0.0437956203,2.8355012 L0.0437956203,11.9016843 L0.0437956203,13.6786562 L0.0437956203,20.1156463 L3.83153579,16.3985111 L17.2990564,16.3985111 C18.7995138,16.3985111 20.0437956,15.1655103 20.0437956,13.6786562 L20.0437956,2.8355012 C20.0254974,1.3305148 18.7995138,0.115646258 17.2807581,0.115646258 Z"
                    id="Shape-path"></path>
            </g>
        </g>
    </svg>),
    category: 'widgets',

    edit: props => {
        wp.ajax.post('mailerlite_gutenberg_forms')
            .then(forms => {
                console.log(forms);
            });

        return ([
            <InspectorControls>
                InspectorControls
            </InspectorControls>,
            <div>
                <Placeholder
                    label={__('Mailerlite sign up form', 'mailerlite')}
                >
                    <SelectControl
                        value={''}
                        options={[
                            {
                                label: __(
                                    'Newness - newest first',
                                    'woo-gutenberg-products-block'
                                ),
                                value: 'date',
                            },
                        ]}
                    />
                    <Button isPrimary={true} style={{marginLeft: 12}}>
                        Select
                    </Button>
                </Placeholder>
            </div>]);
    },

    save: props => {
        return (
            <p className={props.className}>Done</p>
        );
    },
});
