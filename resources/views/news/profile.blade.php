{{-- 課題19-1 --}}
@extends('layouts.front')

@section('content')
  <body>
    <div class="container">
      <hr color="#c0c0c0">
          <div class="row">
            <div class="profiles col-md-10 mx-auto">
              <div class="row">
                <div class="col-md-6">
                  <div class="caption mx-auto">
                    <div class="image mb-3">
                      @if ($profiles[0]->image_path)
                        <img src="{{ asset('storage/image/' . $profiles[0]->image_path) }}">
                      @endif
                    </div>
                  </div>
                </div>
                <table class="table table-bordered">
                  <thead class="thead-light">
                    <tr class="name" align="center">
                      <th colspan="2">プロフィール</th>
                    </tr>
                    <tr class="name" align="center">
                      <th>氏名</th>
                      <th>{{ str_limit($profiles[0]->name, 70) }}</td>
                    </tr>
                    <tr class="gender" align="center">
                      <th>性別</th>
                      <th>{{ str_limit($profiles[0]->gender, 10) }}</td>
                    </tr>
                    <tr class="hobby" align="center">
                      <th>趣味</th>
                      <th>{{ str_limit($profiles[0]->hobby, 100) }}</td>
                    </tr>
                    <tr class="introduction text-center">
                      <th>自己紹介</th>
                      <th>{{ str_limit($profiles[0]->introduction, 250) }}</td>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
      <hr color="#c0c0c0">
    </div>
  </body>
@endsection
