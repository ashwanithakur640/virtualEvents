@if(session()->has('success'))
<div class="alert alert-success">
    <a href="javascript:void(0);" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ session()->get('success') }}
</div>
@endif
@if(session()->has('warning'))
    <div class="alert alert-warning">
        <a href="javascript:void(0);" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session()->get('warning') }}
    </div>
@endif
@if(session()->has('error'))
<div class="alert alert-danger">
    <a href="javascript:void(0);" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ session()->get('error') }}
</div>
@endif
