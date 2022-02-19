<div class="panel panel-chat shoutbox torrent-tip-jar">
    <div class="panel-heading">
        <h4><i class="{{ config("other.font-awesome") }} fa-coins"></i> {{ __('torrent.tip-jar') }}</h4>
    </div>
    <div class="table-responsive">
        <table class="table table-condensed table-bordered table-striped">
            <tbody>
            <tr>
                <td>
                    <div class="col-md-7">
                        <form role="form" method="POST"
                              action="{{ route('tip_uploader', ['id' => $torrent->id]) }}"
                              class="form-inline">
                            @csrf
                            <div class="form-group">
                                <span class="text-green text-bold">{{ __('torrent.define-tip-amount') }}</span>
                                <label>
                                    <input type="number" name="tip" value="0" placeholder="0" class="form-control"
                                           style="width: 80%;">
                                </label>
                                <button type="submit"
                                        class="btn btn-primary">{{ __('torrent.leave-tip') }}</button>
                            </div>
                            <br>
                            <span class="text-green text-bold">{{ __('torrent.quick-tip') }}</span>
                            <br>
                            <button type="submit" value="1000" name="tip" class="btn">1,000</button>
                            <button type="submit" value="2000" name="tip" class="btn">2,000</button>
                            <button type="submit" value="5000" name="tip" class="btn">5,000</button>
                            <button type="submit" value="10000" name="tip" class="btn">10,000</button>
                            <button type="submit" value="20000" name="tip" class="btn">20,000</button>
                            <button type="submit" value="50000" name="tip" class="btn">50,000</button>
                            <button type="submit" value="100000" name="tip" class="btn">100,000</button>
                        </form>
                    </div>
                    <div class="col-md-5">
                        <div class="well" style="box-shadow: none !important;">
                            <h4>{!! __('torrent.torrent-tips', ['total' => $total_tips, 'user' => $user_tips]) !!}
                                .</h4>
                            <span class="text-red text-bold">({{ __('torrent.torrent-tips-desc') }})</span>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>