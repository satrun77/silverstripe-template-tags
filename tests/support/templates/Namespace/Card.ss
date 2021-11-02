<div class="card card--namespace">
    <% content %>
    <div>
        <% if $Name && $Website && $Link %>
            <div>{$Name}</div>
            <div>{$Website}</div>
        <% end_if %>
    </div>
</div>
