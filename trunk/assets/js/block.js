import {
    Placeholder,
    SelectControl,
    Spinner,
    Toolbar,
    withSpokenMessages,
    IconButton
} from '@wordpress/components';

import {
    Component,
    Fragment,
    RawHTML
} from '@wordpress/element';

const {BlockControls} = wp.editor;
const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;
const icon = <svg width="21px" height="21px" viewBox="0 0 21 21">
    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="mailerlitelogo" transform="translate(0.198319, 0.325455)" fill="#09C269" fill-rule="nonzero">
            <path
                d="M17.2807581,0.115646258 L2.78853487,0.115646258 C1.28807741,0.115646258 0.0437956203,1.34864717 0.0437956203,2.8355012 L0.0437956203,11.9016843 L0.0437956203,13.6786562 L0.0437956203,20.1156463 L3.83153579,16.3985111 L17.2990564,16.3985111 C18.7995138,16.3985111 20.0437956,15.1655103 20.0437956,13.6786562 L20.0437956,2.8355012 C20.0254974,1.3305148 18.7995138,0.115646258 17.2807581,0.115646258 Z"
                id="Shape-path"></path>
        </g>
    </g>
</svg>;

export default class MailerLiteFormBlock extends Component {
    constructor() {
        super(...arguments);
        this.state = {
            forms: [],
            loaded: false,
            selected_form: null,
            preview_html: null,
            forms_link: null,
        };
    }

    componentDidMount() {
        wp.ajax.post('mailerlite_gutenberg_forms').then(response => {
            if (response.count) {
                this.setState({
                    forms: response.forms,
                    selected_form: response.forms[0].value,
                    loaded: true,
                    forms_link: response.forms_link
                });
            } else {
                this.setState({
                    loaded: true,
                    forms_link: response.forms_link
                });
            }
        });
    }

    renderPreview() {
        const {form_id} = this.props.attributes;
        const {preview_html} = this.state;
        const {setAttributes} = this.props;

        if (preview_html === null) {
            wp.ajax.post('mailerlite_gutenberg_form_preview', {form_id}).then(response => {
                this.setState({
                    preview_html: response.html,
                });

                // If the form is not found
                if (response.html === false) {
                    setAttributes({
                        editMode: true,
                        form_id: 0
                    });
                }
            });
        }

        return <RawHTML>{preview_html}</RawHTML>;
    }

    renderEditWithForms() {
        const {forms, selected_form} = this.state;
        const {setAttributes} = this.props;
        const {form_id} = this.props.attributes;

        return <Fragment>
            <SelectControl
                value={form_id}
                options={forms}
                onChange={(value) => {
                    this.setState({selected_form: value, preview_html: null});
                }}
            />
            <IconButton
                isPrimary style={{marginLeft: 12}} onClick={() => setAttributes({
                form_id: selected_form,
                editMode: false
            })}
                icon="yes"
            />
        </Fragment>;
    }

    renderEditWithoutForms() {
        const {forms_link} = this.state;

        return <Fragment>
            <div>{__('Create a custom signup form or add a form created using MailerLite.', 'mailerlite')}</div>

            <p>
                <a href={forms_link} className="button button-hero button-primary">
                    {__('Add signup form', 'mailerlite')}
                </a>
            </p>
        </Fragment>;
    }

    renderEdit() {
        const {forms, loaded} = this.state;

        return <Placeholder label={<h3>{__('Mailerlite sign up form', 'mailerlite')}</h3>}>
            {!loaded ?
                <Spinner/>
                :
                forms.length !== 0 ?
                    this.renderEditWithForms()
                    :
                    this.renderEditWithoutForms()
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
    icon: icon,
    category: 'widgets',
    attributes: {
        form_id: {
            type: 'string',
            default: '0'
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
        return <Fragment>[mailerlite_form form_id={props.attributes.form_id}]</Fragment>;
    },
});
