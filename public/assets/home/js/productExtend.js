const route = "products";
extendController = ($scope, $http) => {
    // $scope.name = '';
    // $scope.visible = true;
    $scope.fields = [
        {
            hidden: false,
            field: "name",
            display: "Tên sản phẩm",
            default: "",
            type: "text",
        },
        {
            hidden: false,
            field: "code",
            display: "Mã",
            default: "",
            type: "text",
        },
        {
            hidden: false,
            field: "category.name",
            display: "Tên loại",
            default: "",
            type: "text",
            readonly: true,
        },
        {
            hidden: false,
            field: "quantity",
            display: "Số lượng",
            default: "",
            type: "text",
        },
        {
            hidden: false,
            field: "default_image.file_path",
            display: "Ảnh",
            default: "",
            type: "file",
        },
        {
            hidden: false,
            field: "visible",
            display: "Hiển thị",
            default: true,
            type: "checkbox",
        },
        {
            hidden: true,
            field: "description",
            display: "Mô tả",
            default: "",
            type: "editor",
        },
        // {hidden: true, field: 'visible', display: 'Hiển thị', default: true, type:'checkbox'},
    ];
    $scope.extendQuerys = "with_detail=true&consumable_only=true";
    $scope.id = 0;
    $scope.item = {};
    $scope.selectedCategory = {};
    $scope.order = (column, order) => {
            $scope.column = column;
            $scope.sort = order;
        $scope.getList();
    };
    for (let field of $scope.fields.filter((v) => !v.readonly)) {
        $scope.item[field.field] = field.default;
    }

    $scope.categories = [];
    $http.get("/api/admin/categories?page=1&limit=1000").then((res) => {
        if (res.data.status == true) {
            $scope.categories = res.data.data;
        }
    });
};
