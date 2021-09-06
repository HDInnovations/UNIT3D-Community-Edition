<div>
    <div class="form-group">
        <label for="user" class="sr-only">User</label>
        <input type="text" id="user" class="form-control" autocomplete="off" placeholder="Search users" x-on:input.debounce="search" x-ref="search">
    </div>

    {{ $suggestions }}
</div>

<script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
    function userSearchState() {
        return {
            suggestions: [],

            search (e) {
                fetch(`/api/search/users?q=${e.target.value}`)
                    .then(response => response.json())
                    .then((suggestions) => {
                        this.suggestions = suggestions
                    })
            }
        }
    }
</script>