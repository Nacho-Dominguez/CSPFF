<?php

class A25_Broadcast extends A25_StrictObject
{
	private $id;
	private $title;
	private $summary;
	private $blog_link;
	
	public function __construct($id, $title, $summary, $blog_link)
	{
		$this->id = $id;
		$this->title = $title;
		$this->summary = $summary;
		$this->blog_link = $blog_link;
	}
	public function render()
	{
		A25_Include_Broadcast::load();
		
		$count = Doctrine_Query::create()->select()
				->from('HideBroadcast')
				->where('broadcast_id = ?', $this->id)
				->andWhere('user_id = ?', A25_DI::UserId())
				->count();
		if ($count == 0) {
			?>
			<div style=" display: inline-block;">
			<div id="broadcast<?php echo $this->id; ?>" style="padding: 6px;
				 text-align: left; width: 280px;">
				<div style="background-color: #ddfcdd; padding: 8px 12px 8px 12px; cursor: pointer;
					 border: 1px solid #ccddcc; border-radius: 8px;">
					<a href="<?php echo $this->blog_link; ?>"
					   style="text-decoration: none; color: #777777;">
					<div style="font-weight: bold; font-size: 15px; color: #5588dd;">
						<?php echo $this->title; ?>
					</div>
					<div style="font-size: 12px; color: #555555">
						<?php echo $this->summary; ?>
					</div>
					</div>
					</a>
				<div style="font-size: 9px; margin-right: 6px; margin-top: 3px;
					 margin-bottom: 6px; color: #777777; font-weight: bold;
					 display: inline-block; text-align: right; float: right;
					 cursor: pointer;" onclick="closeBroadcast(<?php echo $this->id; ?>)">
					&times; hide this message
				</div>
				<div style="font-size: 9px; padding-left: 6px; margin-top: 3px;
					 margin-bottom: 6px; color: #777777; font-weight: bold;
					 cursor: pointer;">
					<a href="<?php echo $this->blog_link; ?>" target="_blank"
					   style="text-decoration: none; color: #777777;">>> more info</a>
				</div>
			</div>
			</div>
			<?php
		}
	}
}