<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
    public function index(Request $request)
    {
        return view('user_addresses.index', [
            'addresses' => $request->user()->addresses,
        ]);
    }

    public function create()
    {
        return view('user_addresses.create_and_edit', ['address' => new UserAddress()]);
    }

    public function store(UserAddressRequest $request)
    {
        $request->user()->addresses()->create($request->all());

        return redirect()->route('user_addresses.index');
    }

    /**
     * @param UserAddress $user_address
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(UserAddress $user_address)
    {
        $this->authorize('own', $user_address);

        return view('user_addresses.create_and_edit', ['address' => $user_address]);
    }

    /**
     * @param UserAddressRequest $request
     * @param UserAddress $user_address
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UserAddressRequest $request, UserAddress $user_address)
    {
        $this->authorize('own', $user_address);

        $user_address->update($request->all());

        return redirect()->route('user_addresses.index');
    }

    /**
     * @param UserAddress $user_address
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(UserAddress $user_address)
    {
        $this->authorize('own', $user_address);

        $user_address->delete();

        return [];
    }
}
