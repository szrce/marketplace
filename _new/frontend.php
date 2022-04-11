<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Trendyol categories</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

				<div class="row">
			    <div class="col-4">
			    <div class="form-group">

			      <input type="search" class="form-control" id="exampleInputsearch" aria-describedby="exampleInputsearch" placeholder="search categories like saat / hediye">
			      <small id="exampleInputsearch" class="form-text text-muted">search from trendyol categories.</small>
			    </div>

			      <div class="list-group" id="list-tab" role="tablist">

			<?php

				require_once("custom_trendyol_helper_sezer/function_trendyol.php");
				$categoryList = Request('get_category')['categories'];
				search_category($categoryList);
			?>
			</div>
		</div>

		<div class="col-8">
					<div class="tab-content" id="nav-tabContent">
						<div class="tab-pane fade show " id="list-387" role="tabpanel" aria-labelledby="list-387-list">
									<form id="subcatform">
									</form>
						</div>
					</div>
				</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
