<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use App\Handlers\ImageUploadHandler;

use App\Http\Requests\UserRequest;

class UsersController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }

    /**
     * ユーザー表示
     * @author kaku
     * @since 2019.02.15
     * @$user model
     * @return view
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * ユーザー編集
     * @author kaku
     * @since 2019.02.15
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }


    /**
     * ユーザー編集情報保存
     * @author kaku
     * @since 2019.02.15
     */
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->all();


        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatars', $user->id,416);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);

        return redirect()->route('users.show', $user->id)->with('success', '個人情報が更新しました');
    }

}
