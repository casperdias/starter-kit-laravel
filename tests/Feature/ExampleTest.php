<?php

test('returns a successful response12142', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
