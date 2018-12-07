<? if( !empty( $dataset ) ): ?>

<table width="100%" class="accounts">

<thead>
	<tr>
		<th style="white-space:nowrap">Username</th>
		<th style="white-space:nowrap">Registration Date</th>
		<th style="white-space:nowrap">Last Login Date</th>
	</tr>
</thead>

<tbody>
<? foreach( $dataset as $data ): ?>
<tr class="accounts-content">
	<td><?= $data['username'] ?></td>
	<td><?= $data['registration_date'] ?></td>
	<td><?= $data['last_login_date'] ?></td>
</tr>
<? endforeach; ?>
</tbody>

</table>

<? if( !empty( $pagination_html ) ): ?>
<? $this->load->view('degeo-foundation/pagination') ?>
<? endif; ?>

<? endif; ?>