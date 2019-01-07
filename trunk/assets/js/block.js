import {
    Button,
    Placeholder,
    SelectControl, Spinner, Toolbar, withSpokenMessages,
} from '@wordpress/components';

import {Component, Fragment} from '@wordpress/element';

const {BlockControls} = wp.editor;
const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;


export default class MailerLiteFormBlock extends Component {
    constructor() {
        super(...arguments);
        this.state = {
            forms: [],
            loaded: false,
            selected_form: null
        };
    }

    componentDidMount() {
        wp.ajax.post('mailerlite_gutenberg_forms').then(forms => {
            this.setState({forms: forms, selected_form: forms[0].value, loaded: true});
        });
    }

    renderPreview() {
        const {form_id, editMode} = this.props.attributes;

        return <Fragment>
            preview {form_id}
        </Fragment>;
    }

    renderEdit() {
        const {forms, loaded, selected_form} = this.state;
        const {setAttributes} = this.props;

        return <Placeholder label={__('Mailerlite sign up form', 'mailerlite')}>
            {!loaded ?
                <Spinner/>
                :
                <Fragment>
                    <SelectControl
                        options={forms}
                        onChange={(value) => {
                            this.setState({selected_form: value});
                        }}
                    />
                    <Button isPrimary style={{marginLeft: 12}} onClick={() => setAttributes({
                        form_id: selected_form,
                        editMode: false
                    })}>
                        Select
                    </Button>
                </Fragment>
            }
        </Placeholder>;
    }

    render() {
        const {editMode} = this.props.attributes;
        const {setAttributes} = this.props;

        return (
            <Fragment>
                <BlockControls>
                    <Toolbar
                        controls={[
                            {
                                icon: 'edit',
                                title: __('Edit'),
                                onClick: () => setAttributes({editMode: !editMode}),
                                isActive: editMode,
                            },
                        ]}
                    />
                </BlockControls>
                {editMode ? this.renderEdit() : this.renderPreview()}
            </Fragment>
        );
    }
}

const WrappedMailerLiteFormBlock = withSpokenMessages(
    MailerLiteFormBlock
);

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

    attributes: {
        form_id: {
            type: 'integer',
            default: 0,
        },
        editMode: {
            type: 'boolean',
            default: true,
        },
    },

    edit: props => {
        return <WrappedMailerLiteFormBlock {...props} />;
    },

    save: props => {
        return <div>hi</div>;
    },
});

