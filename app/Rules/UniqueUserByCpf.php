<?php
namespace App\Rules;

use App\Models\User;
use App\Helper\Number;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;

class UniqueUserByCpf implements Rule, DataAwareRule
{
    protected $attribute;

     /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
 
        return $this;
    }

    public function setAttribute(string $attribute)
    {
        $this->attribute = $attribute;
    }

    public function getAttribute()
    {
        switch ($this->attribute) {
            case 'form2.cpf':
                $attribute = "CPF Prefeito";
                break;
            case 'form3.cpf':
                $attribute = "CPF Gestor político";
                break;
            default:
                $attribute = "CPF";
                break;
        }

        return $attribute;
    }

    public function passes($attribute, $value)
    {
        $this->setAttribute($attribute);

        $id = isset($this->data['id']) ? User::findByUUID($this->data['id'])->id : null; 
        $user = User::where('cpf', Number::getOnlyNumber($value))
            ->where('id', '!=', $id)
            ->whereNull('deleted_at')
            ->first();

        return is_null($user);     
    }   

    public function message()
    {
        return 'O campo '. $this->getAttribute() . ' já está sendo utilizado.';
    }
}
