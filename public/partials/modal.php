<?php
/**
 *
 * Payment Modal with time tracking
 *
 */
?>


<!-- Link to open the modal -->
<p><a href="#ex1" rel="modal:open">Open Modal</a></p>



<!-- Modal HTML embedded directly into document -->
<div id="ex1" class="modal">

	<div class="content">
		<div class="top-header">
			<div class="header">
				<div class="header__icon">
					<img class="header__icon__img" src="<?php echo plugin_dir_url( __FILE__ ) . '../assets/images/logo_green.png' ?>">
				</div>
				<div class="close-icon">
					<img src="<?php echo plugin_dir_url( __FILE__ ) . '../assets/images/close-icon.svg' ?>">
				</div>
			</div>

			<div class="timer-row">
				<div class="timer-row__progress-bar" style="width: 4.16533%;"></div>
					<div class="timer-row__spinner">
						<bp-spinner>
							<svg xml:space="preserve" style="enable-background:new 0 0 50 50;" version="1.1" viewBox="0 0 50 50" x="0px" xmlns="http://www.w3.org/2000/svg" y="0px">
								<path d="M11.1,29.6c-0.5-1.5-0.8-3-0.8-4.6c0-8.1,6.6-14.7,14.7-14.7S39.7,16.9,39.7,25c0,1.6-0.3,3.2-0.8,4.6l6.1,2c0.7-2.1,1.1-4.3,1.1-6.6c0-11.7-9.5-21.2-21.2-21.2S3.8,13.3,3.8,25c0,2.3,0.4,4.5,1.1,6.6L11.1,29.6z"></path>
                            </svg>
						</bp-spinner>
					</div>
					<div class="timer-row__message">
						<span>
                            <span i18n="">Awaiting Payment...</span>
						</span>
					</div>
				<div class="timer-row__time-left">14:22</div>
			</div><?php //.timer-row?>
		</div> <?php //.top-header?>
	</div> <?php //.content?>


</div>
