{% extends views/layouts/Default.php %}

{% block content %}
<input type="hidden" id="url" data-url="{{ route('') }}">
<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a class="display-6" style="text-decoration: none;" href="{{ route('/logs') }}">Logs</a>
		</li>
	</ol>
</nav>
<div class="table-responsive table-responsive-lg">
	<table id="result" class="table table-striped table-bordered table-condensed table-sm dt-responsive nowrap" style="width: 100%;">
		<thead>
			<tr>
				<th>Agent</th>
				<th>Description</th>
				<th>Entity</th>
				<th>Platform</th>
				<th>Date</th>
		</thead>
		<tbody></tbody>
		<tfoot>
			<tr>
				<th class="filterFooter">Agent</th>
				<th class="filterFooter">Description</th>
				<th class="filterFooter">Entity</th>
				<th class="filterFooter">Platform</th>
				<th class="filterFooter">Date</th>
			</tr>
		</tfoot>
	</table>
</div>
<script src="{{ asset('scripts/log.js') }}"></script>

{% endblock %}