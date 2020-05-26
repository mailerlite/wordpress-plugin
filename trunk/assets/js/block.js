import {
    Placeholder,
    SelectControl,
    PanelBody,
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

const {
    BlockControls,
    InspectorControls,
} = wp.editor;

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
            edit_link: null,
            forms_link: null,
        };
    }

    componentDidMount() {
        wp.ajax.post('mailerlite_gutenberg_forms', {ml_nonce: mailerlite_vars.ml_nonce}).then(response => {
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
        const {preview_html, edit_link} = this.state;
        const {setAttributes} = this.props;

        if (preview_html === null) {
            wp.ajax.post('mailerlite_gutenberg_form_preview', {form_id, ml_nonce: mailerlite_vars.ml_nonce}).then(response => {
                this.setState({
                    preview_html: response.html,
                    edit_link: response.edit_link
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

        return <Fragment>
            <InspectorControls key="inspector">
                <br/>
                <a href={edit_link} target="_blank" class="button button-primary">
                    {__('Edit form', 'mailerlite')}
                </a>
            </InspectorControls>
            <RawHTML>{preview_html}</RawHTML>
        </Fragment>;
    }

    renderEditWithForms() {
        const {forms, selected_form} = this.state;
        const {setAttributes} = this.props;
        const {form_id} = this.props.attributes;

        return <Fragment>
            <select>
                {forms.map(form =>
                    <option key={form.value} value={form.value}>{form.label}</option>
                )};
            </select>
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

        return <Placeholder label={<h3>{__('MailerLite sign up form', 'mailerlite')}</h3>}>
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
    title: __('MailerLite sign up form', 'mailerlite'),
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
