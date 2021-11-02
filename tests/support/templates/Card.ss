<div class="card{$Modifier}">
    <% content %>
    <div>
        <% if $Name && $Website && $Link %>
            <div>{$Name}</div>
            <div>{$Website}</div>
        <% end_if %>
    </div>
</div>
