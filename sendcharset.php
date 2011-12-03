<?php

/**
 * Select sending charset coding system.
 */
class sendcharset extends rcube_plugin
{

  function init()
  {
    $this->load_config();
    $this->add_hook('preferences_list', array($this, 'show_option'));
    $this->add_hook('preferences_save', array($this, 'save'));
    $this->add_hook('template_object_composebody', array($this, 'append'));
  }

  /**
   * Show an option in compose preference.
   */
  function show_option($attrib)
  {
    global $RCMAIL;

    if ($attrib['section'] == 'compose') {
      $field_id = 'rcmfd_sendcharset';
      $selected = $this->get_charset();
      $input = $RCMAIL->output->charset_selector(array('name'=>'_sendcharset',
						       'id'=>$field_id,
						       'selected'=>$selected));
      $attrib['blocks']['main']['options']['sendcharset'] =
	array( 'title'=>html::label($field_id, Q(rcube_label('charset'))),
	       'content'=>$input);
      return $attrib;
    } else {
      return null;
    }
  }

  /**
   * Save preference option "sendcharset".
   */
  function save($attrib) {
    if ($attrib['section'] == 'compose') {
      if (isset($_POST['_sendcharset'])) {
	$attrib['prefs']['sendcharset'] =
	  get_input_value('_sendcharset', RCUBE_INPUT_POST);
      }
    }
    return $attrib;
  }

  /**
   * Add hidden input element to end of composebody
   */
  function append($attrib)
  {
    $input = new html_inputfield(array('type'=>'hidden', 'name'=>'_charset'));
    $attrib['content'] .= "\n".$input->show($this->get_charset());
    return $attrib;
  }

  /**
   * Get preference option "sendcharset".
   * If it is not set, return GUI coding system.
   */
  private function get_charset() {
    global $RCMAIL, $OUTPUT;
    $config = $RCMAIL->config->all();

    return isset($config['sendcharset']) ?
      $config['sendcharset'] : $OUTPUT->get_charset();
  }
}
