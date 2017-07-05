<?php

class contact_command extends base_command {
	public $name = 'contact';
	public $description = 'تماس با ما';

	function run($chat_id, $text, $message, $state) {
		global $telegram, $db;
		$message_id = $message->getMessageId();
		switch ($state) {
			case CONTACT:
				$btn = create_glassy_btn('پاسخ', 'admn_answr_cntct', ['c' => $chat_id, 'm' => $message_id]);
				$reply_markup = create_glassy_keyboard([[$btn]]);

				send_message_to_admin(create_report_from_a_user_message('یک تماس جدید', $text), $reply_markup);
				reset_state(SUCCESS_MESSAGE);
				break;
			
			case CONTACT_ADMIN_ANSWER:
				// this the answer of admin to the user who has sent it
				$data = $db->get_data();
				$dest_chat_id = $data['chat_id'];
				$dest_message_id = $data['message_id'];
				$fullname = get_fullname();

				$btn = create_glassy_btn('پاسخ', 'admn_answr_cntct', ['c' => $chat_id, 'm' => $message_id]);
				$reply_markup = create_glassy_keyboard([[$btn]]);

				$telegram->sendMessage([
					'chat_id' => $dest_chat_id,
					'text' => '*پاسخ به پیام شما از طرف:* ' . $fullname . PHP_EOL . PHP_EOL . $text,
					'parse_mode' => 'Markdown',
					'reply_to_message_id' => $dest_message_id,
					'reply_markup' => $reply_markup,
				]);
				reset_state();
				break;
			default:
				$db->set_state(CONTACT);
				reply('لطفا پیام تان را بفرستید', true);
				break;
		}
	}
}
