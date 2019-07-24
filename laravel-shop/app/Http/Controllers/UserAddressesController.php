<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use App\Services\UserAddressService;
use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
    protected $userAddressService;

    public function __construct(UserAddressService $userAddressService)
    {
        $this->userAddressService = $userAddressService;
    }

    public function index(Request $request)
    {
        $addresses = $this->userAddressService->userAddresses($request->user());

        return view('user_addresses.index', compact('addresses'));
    }

    public function create()
    {
        $user_address = new UserAddress();

        return view('user_addresses.create_and_edit', compact('user_address'));
    }

    public function store(UserAddressRequest $request)
    {
        $this->userAddressService->storeUserAddress($request->user(), $request->all());

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

        return view('user_addresses.create_and_edit', compact('user_address'));
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

        $this->userAddressService->updateUserAddress($user_address, $request->all());

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

        $this->userAddressService->deleteUserAddress($user_address);

        return [];
    }
}
