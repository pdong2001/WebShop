const route = "product-details";
extendController = ($scope, $http) => {
    // $scope.name = '';
    // $scope.visible = true;
    $scope.fields = [
        {
            hidden: false,
            field: "product.name",
            column : "product_id",
            display: "Tên sản phẩm",
            default: "",
            type: "text",
            readonly: true,
        },
        // {
        //     hidden: false,
        //     field: "size",
        //     display: "Màu sắc",
        //     default: "",
        //     type: "text",
        // },
        {
            hidden: false,
            field: "size",
            display: "Kích thước",
            default: "",
            type: "text",
        },
        {
            hidden: false,
            field: "in_price",
            display: "Giá nhập",
            default: 0,
            type: "number",
            readonly: true
        },
        {
            hidden: false,
            field: "out_price",
            display: "Giá bán",
            default: 0,
            type: "number",
        },
        {
            hidden: false,
            field: "remaining_quantity",
            display: "Số lượng",
            default: 0,
            type: "number",
        },
        {
            hidden: false,
            field: "image.file_path",
            column : "default_image",
            display: "Ảnh",
            default: "",
            type: "file",
            readonly: true,
        },
        {
            hidden: false,
            field: "unit",
            display: "ĐVT",
            default: "",
            type: "text",
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
            field: "default_image",
            column : "default_image",
            display: "Ảnh",
            default: "",
            type: "file",
            readonly: false,
        },
    ];
    $scope.id = 0;
    $scope.item = {};
    $scope.selectedProduct = {};
    $scope.extendQuerys = "with_product=true";

    for (let field of $scope.fields.filter((v) => !v.readonly)) {
        $scope.item[field.field] = field.default;
    }

    $scope.showEdit = (item) => {
        console.log(item);
        const file = document.querySelector("input[type='file']");
        if (file != null) value = "";
        $scope.id = item.id;
        $scope.selectedProduct =
            $scope.products.find((v) => v.id == item.product?.id??item.product_id) ?? {};
        for (let field of $scope.fields.filter((v) => !v.readonly)) {
            $scope.item[field.field] = item[field.field];
        }
        $scope.editting = true;
        $scope.deleting = false;
    };

    $scope.showAddNew = () => {
        for (let field of $scope.fields.filter((v) => !v.readonly)) {
            $scope.item[field.field] = field.default;
        }
        const file = document.querySelector("input[type='file']");
        if (file != null) value = "";
        $scope.editting = false;
        $scope.deleting = false;
    };
    $scope.save = () => {
        const fileE = document.querySelector("input[type='file']");
        let file;
        if (fileE != null) file = fileE.files[0];
        let item = {};
        for (let field of $scope.fields.filter((v) => !v.readonly)) {
            item[field.field] = $scope.item[field.field];
        }
        let index = document.getElementById("select")?.selectedIndex ?? -1;
        if (index >= 0) {
            $scope.selectedProduct = $scope.products[index];
            item.product_id = $scope.selectedProduct.id;
        }
        if (file != undefined && file != null) {
            $scope.upLoadFile(file, $scope.baseHref + "/api/upload").then((res) => {
                if (res.data.status == true) {
                    item.default_image = res.data.data.id;
                }
                if ($scope.editting) {
                    $scope.update($scope.id, item);
                } else if ($scope.deleting) {
                    $scope.delete($scope.id);
                } else {
                    $scope.create(item);
                }
            });
        } else {
            item.product_id = $scope.selectedProduct.id;
            if ($scope.editting) {
                $scope.update($scope.id, item);
            } else if ($scope.deleting) {
                $scope.delete($scope.id);
            } else {
                $scope.create(item)
            }
        }
    };
    $scope.showDelete = (id) => {
        $scope.id = id;
        $scope.deleting = true;
        $scope.editting = false;
    };
    $scope.products = [];
    $http.get( $scope.baseHref +"/api/admin/products?page=1&limit=1000").then((res) => {
        if (res.data.status == true) {
            $scope.products = res.data.data;
        }
    });
    $scope.change = () => {
        console.log($scope.file);
    };
};
