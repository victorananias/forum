<script>
    import algoliasearch from 'algoliasearch/lite';
    import { AisInstantSearch,
            AisSearchBox,
            AisHits,
            AisConfigure,
            AisHighlight,
            AisRefinementList
    } from 'vue-instantsearch';

    export default {
        components: {
            AisInstantSearch,
            AisSearchBox,
            AisHits,
            AisConfigure,
            AisHighlight,
            AisRefinementList
        },
        data() {
            return {
                searchClient: algoliasearch(
                    process.env.MIX_ALGOLIA_APP_ID,
                    process.env.MIX_ALGOLIA_KEY
                )
            }
        }
    }
</script>